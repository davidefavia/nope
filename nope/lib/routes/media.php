<?php

namespace Nope;

use Respect\Validation\Validator as v;

$app->group(NOPE_ADMIN_ROUTE . '/content/media', function() {

  $this->get('', function($request, $response) {
    $rpp = 12;
    $currentUser = User::getAuthenticated();
    if(!$currentUser->can('media.read')) {
      return $response->withStatus(403);
    }
    $queryParams = (object) $request->getQueryParams();
    $params = Utils::getPaginationTerms($request, $rpp);
    $contentsList = Media::findAll([
      'text' => $params->query,
      'mimetype' => $queryParams->mimetype
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
        $filenameWithoutExtension = implode('.',$p);
        $filenameWithoutExtension = str_replace('_', ' ', $filenameWithoutExtension);
        $filenameWithoutExtension = trim(str_replace('-', ' ', $filenameWithoutExtension));
        $size = $_FILES['file']['size'];
        $media = new Media();
        $media->title = $filenameWithoutExtension;
        $media->description = '';
        $media->mimetype = $type;
        $media->filename = $uniqueFilename;
        $media->size = $size;
        $media->starred = false;
        $media->setAuthor($currentUser);
        /*if($tags) {
        	$media->setTags($tags);
        }*/
        $media->save();
        $media = Media::findById($media->id);
      } catch(Exception $e) {
        #unlink($uploadfile);
        throw $e;
      }
    } else {
      @unlink($uploadfile);
      throw new \Exception();
    }
    return $response->withJson(['currentUser' => $currentUser, "data" => $media]);
  });

  $this->get('/{id}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('media.read')) {
      $content = Media::findById($args['id']);
    }
    return $response->withJson(['currentUser' => $currentUser, "data" => $content]);
  });

  $this->put('/{id}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    $body = $request->getParsedBody();
    if($currentUser->can('media.update')) {
      $fields = ['title', 'description', 'tags', 'starred'];
      $contentToUpdate = new Media($args['id']);
      if($contentToUpdate) {
        $contentToUpdate->import($body, $fields);
        $contentToUpdate->save();
        return $response->withJson(['currentUser' => $currentUser, "data" => $contentToUpdate]);
      } else {
        return $response->withStatus(404);
      }
    } else {
      return $response->withStatus(403);
    }
  });

  $this->delete('/{id}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('media.delete')) {
      $contentToDelete = new Media($args['id']);
      $path = $contentToDelete->getPath();
      $contentToDelete->delete();
      @unlink($path);
    }
    return $response->withJson(['currentUser' => $currentUser]);
  });

});
