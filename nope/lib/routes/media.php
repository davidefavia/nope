<?php

namespace Nope;

use Respect\Validation\Validator as v;
use Intervention\Image\ImageManagerStatic as Image;
use Stringy\StaticStringy as S;

$app->group(NOPE_ADMIN_ROUTE . '/content/media', function() {

  $this->get('', function($request, $response) {
    $rpp = NOPE_MEDIA_RPP;
    $currentUser = User::getAuthenticated();
    if(!$currentUser->can('media.read')) {
      return $response->withStatus(403);
    }
    $queryParams = (object) $request->getQueryParams();
    $params = Utils::getPaginationTerms($request, $rpp);
    $contentsList = Media::findAll([
      'text' => $params->query,
      'mimetype' => $queryParams->mimetype,
      'excluded' => ($queryParams->excluded?explode(',', $queryParams->excluded):null)
    ], $params->limit, $params->offset, $count);
    $metadata = Utils::getPaginationMetadata($params->page, $count, $rpp);
    return $response->withJson([
      'currentUser' => $currentUser,
      'metadata' => $metadata,
      'data' => $contentsList
    ])->withHeader('Link', json_encode($metadata));
  });

  $this->post('/upload', function($request, $response) {
    $currentUser = User::getAuthenticated();
    if(!$currentUser->can('media.create')) {
      return $response->withStatus(403);
    }
    $filename = basename($_FILES['file']['name']);
    $filename = str_replace(' ', '-', $filename);
    $uniqueFilename = Utils::getUniqueFilename($filename, NOPE_UPLOADS_DIR);
    $uploadfile = NOPE_UPLOADS_DIR . $uniqueFilename;
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
      /**
       * @DEPRECATED mime_content_type
       */
      try {
        $type = $_FILES['file']['type'];
        $p = explode('.',$filename);
        array_pop($p);
        if(count($p)) {
          $filenameWithoutExtension = implode('.',$p);
          $filenameWithoutExtension = str_replace('_', ' ', $filenameWithoutExtension);
          $filenameWithoutExtension = trim(str_replace('-', ' ', $filenameWithoutExtension));
        } else {
          $filenameWithoutExtension = $filename;
        }
        $size = $_FILES['file']['size'];
        $media = new Media();
        $media->title = $filenameWithoutExtension;
        $media->body = '';
        $media->mimetype = $type;
        $media->filename = $uniqueFilename;
        $media->size = $size;
        $media->starred = false;
        $media->type = null;
        $media->provider = null;
        $media->setAuthor($currentUser);
        $media->save();
        $media = Media::findById($media->id);
      } catch(Exception $e) {
        @unlink($uploadfile);
        throw $e;
      }
    } else {
      @unlink($uploadfile);
      throw new \Exception();
    }
    return $response->withJson(['currentUser' => $currentUser, "data" => $media]);
  });

  $this->post('/import', function($request, $response) {
    $currentUser = User::getAuthenticated();
    if(!$currentUser->can('media.create')) {
      return $response->withStatus(403);
    }
    $body = (object) $request->getParsedBody();
    try {
      $media = new Media();
      $info = \Embed\Embed::create($body->url);
      $imageUrl = $info->images[0]['value'];
      $media->title = (string) S::truncate($info->title, 255);
      $media->body = $info->description;
      $media->type = $info->type;
      $p = explode('/', $info->images[0]['value']);
      $filename = $p[count($p)-1];
      $media->provider = $info->providerName;
      $media->mimetype = $info->images[0]['mime'];
      $media->size = $info->images[0]['size'];
      $uniqueFilename = Utils::getUniqueFilename((string) S::truncate($filename, 100), NOPE_UPLOADS_DIR);
      file_put_contents(NOPE_UPLOADS_DIR . $uniqueFilename, file_get_contents($imageUrl));
      $ext = pathinfo(NOPE_UPLOADS_DIR . $uniqueFilename, PATHINFO_EXTENSION);
      if(!$ext && $media->isImage()) {
        @unlink(NOPE_UPLOADS_DIR . $uniqueFilename);
        $uniqueFilename .= '.' . explode('/', $media->mimetype)[1];
        file_put_contents(NOPE_UPLOADS_DIR . $uniqueFilename, file_get_contents($imageUrl));
      }
      $media->filename = $uniqueFilename;
      $media->url = $info->url;
      $media->starred = false;
      $media->setAuthor($currentUser);
      // Why save media before tags? It is needed to generate ID, then build tag relations!
      $media->save();
      if($info->tags) {
        $media->setTags($info->tags);
        $media->save();
      }
      $media = Media::findById($media->id);
    } catch(\Exception $e) {
      throw $e;
    }
    return $response->withJson(['currentUser' => $currentUser, "data" => $media]);
  });

  $this->get('/{id:[0-9]+}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('media.read')) {
      $content = Media::findById($args['id']);
    }
    return $response->withJson(['currentUser' => $currentUser, "data" => $content]);
  });

  $this->put('/{id:[0-9]+}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    $body = $request->getParsedBody();
    if($currentUser->can('media.update')) {
      $fields = ['title', 'body', 'tags', 'starred'];
      if($currentUser->can('media.custom')) {
        $fields[] = 'custom';
      }
      $contentToUpdate = new Media($args['id']);
      if($contentToUpdate) {
        $contentToUpdate->import($body, $fields);
        $contentToUpdate->save();
        return $response->withJson([
          'currentUser' => $currentUser,
          'data' => $contentToUpdate
        ]);
      } else {
        return $response->withStatus(404);
      }
    } else {
      return $response->withStatus(403);
    }
  });

  $this->put('/{id:[0-9]+}/edit', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    $body = $request->getParsedBody();
    if($currentUser->can('media.update')) {
      $contentToUpdate = new Media($args['id']);
      if($contentToUpdate) {
        $path = $contentToUpdate->getPath();
        $img = Image::make($path);
        $queryParams = (object) $request->getQueryParams();
        if($queryParams->rotate) {
          $img->rotate($queryParams->rotate);
          $img->save($path, 100);
          $contentToUpdate->save();
          return $response->withJson([
            'currentUser' => $currentUser,
            'data' => $contentToUpdate
          ]);
        } else {
          return $response->withStatus(400, 'Rotation angle must differ from zero.');
        }
      } else {
        return $response->withStatus(404);
      }
    } else {
      return $response->withStatus(403);
    }
  });

  $this->delete('/{id:[0-9]+}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('media.delete')) {
      $contentToDelete = new Media($args['id']);
      $path = $contentToDelete->getPath();
      $contentToDelete->delete();
      @unlink($path);
    }
    return $response->withJson(['currentUser' => $currentUser]);
  });

  $this->delete('/list', function($request, $response) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('media.delete')) {
      $queryParams = $request->getQueryParams();
      $idsList = explode(',', $queryParams['id']);
      foreach ($idsList as $id) {
        $contentToDelete = new Media((int) $id);
        $path = $contentToDelete->getPath();
        $contentToDelete->delete();
        @unlink($path);
      }
    }
    return $response->withJson(['currentUser' => $currentUser]);
  });

  $this->post('/tags', function($request, $response) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('media.update')) {
      $body = (object) $request->getParsedBody();
      $action = $body->action;
      $tags = $body->tags;
      $idsList = explode(',', $body->id);
      switch($action) {
        default:
          break;
        case 'add':
          foreach ($idsList as $id) {
            $contentToUpdate = new Media((int) $id);
            $contentToUpdate->addTags($tags);
            $contentToUpdate->save();
          }
          break;
        case 'replace':
          foreach ($idsList as $id) {
            $contentToUpdate = new Media((int) $id);
            $contentToUpdate->setTags($tags);
            $contentToUpdate->save();
          }
          break;
        case 'remove':
          foreach ($idsList as $id) {
            $contentToUpdate = new Media((int) $id);
            $contentToUpdate->removeTags($tags);
            $contentToUpdate->save();
          }
          break;
        case 'removeall':
          foreach ($idsList as $id) {
            $contentToUpdate = new Media((int) $id);
            $contentToUpdate->removeTags();
            $contentToUpdate->save();
          }
          break;
      }
    }
    return $response->withJson(['currentUser' => $currentUser]);
  });

});
