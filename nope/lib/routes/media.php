<?php

namespace Nope;

use Respect\Validation\Validator as v;

$app->group(NOPE_ADMIN_ROUTE . '/content/media', function() {

  $this->get('', function($req, $res) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('media.read')) {
      $contentsList = Media::findAll();
    } else {
      return $res->withStatus(403);
    }
    $body = $res->getBody();
    $body->write(json_encode(['currentUser' => $currentUser, "data" => $contentsList]));
    return $res->withBody($body);
  });

  $this->post('/upload', function($req, $res) {
    $currentUser = User::getAuthenticated();
    if(!$currentUser->can('media.create')) {
      return $res->withStatus(403);
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
    $body = $res->getBody();
    $body->write(json_encode(['currentUser' => $currentUser, "data" => $media]));
    return $res->withBody($body)->withHeader('Content-Type', 'application/json');
  });

  $this->post('', function($req, $res, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('media.create')) {
      $fields = ['title'];
      $contentToCreate = new Media();
      $body = $req->getParsedBody();
      $contentToCreate->import($body, $fields);
      $contentToCreate->setAuthor($currentUser);
      $contentToCreate->save();
      $body = $res->getBody();
      $body->write(json_encode(['currentUser' => $currentUser, "data" => $contentToCreate]));
      return $res->withBody($body);
    } else {
      return $res->withStatus(403);
    }
  });

  $this->get('/{id}', function($req, $res, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('media.read')) {
      $content = Media::findById($args['id']);
    }
    $body = $res->getBody();
    $body->write(json_encode(['currentUser' => $currentUser, "data" => $content]));
    return $res->withBody($body);
  });

  $this->put('/{id}', function($req, $res, $args) {
    $currentUser = User::getAuthenticated();
    $body = $req->getParsedBody();
    if($currentUser->can('media.update')) {
      $fields = ['title'];
      $contentToUpdate = new Media($args['id']);
      if($contentToUpdate) {
        $contentToUpdate->import($body, $fields);
        $contentToUpdate->save();
        $body = $res->getBody();
        $body->write(json_encode(['currentUser' => $currentUser, "data" => $contentToUpdate]));
        return $res->withBody($body);
      } else {
        return $res->withStatus(404);
      }
    } else {
      return $res->withStatus(403);
    }
  });

  $this->delete('/{id}', function($req, $res, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('media.delete')) {
      $contentToDelete = new Media($args['id']);
      $contentToDelete->delete();
    }
    $body = $res->getBody();
    $body->write(json_encode(['currentUser' => $currentUser]));
    return $res->withBody($body);
  });

});
