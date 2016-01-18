<nope-modal class="upload" title="Upload media">
  <nope-modal-body>
    <a href="" nope-upload="onDone();" accept="{{$parent.accept}}" class="btn btn-block btn-default">Upload <i class="fa fa-upload"></i></a>
    <hr>
    <nope-import on-done="onDone();"></nope-import>
  </nope-modal-body>
</nope-modal>
