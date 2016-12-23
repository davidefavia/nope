<div class="container-fluid">
  <div id="container" class="row">
    <div id="sidebar" class="col col-md-3 col-sm-3 col-lg-2" ng-if="!nope.isIframe">
      <div class="nav-container">
        <div class="list-group">
          <a href="#/user/view/{{currentUser.id}}" class="list-group-item">
            <img class="rounded-circle" ng-src="{{currentUser.cover.preview.profile || assetsPath + 'assets/img/nope.png'}}" /> {{currentUser.getFullName()}}
          </a>
          <?php foreach ($menuItems as $key => $item) { ?>
            <a class="list-group-item" id="menu-item-<?php echo $key; ?>" <?php if($item['activeWhen']) { ?>ng-class="{active:<?php echo $item['activeWhen']; ?>}"<?php }?> <?php if($item['permission']) { echo 'nope-can="'.$item['permission'].'"';} ?> <?php if($item['role']) { echo 'nope-role="'.$item['role'].'"';} ?> <?php foreach($item['attrs'] as $key => $attr) { echo "$key=\"$attr\" ";} ?>><i class="<?php echo $item['icon']; ?>"></i> <?php echo $item['label']; ?>
            </a>
          <?php } ?>
        </div>
      </div>
    </div>
    <div id="main" class="col col-md-9 col-lg-10 offset-md-3 offset-sm-3 offset-lg-2" ng-class="{'flex-items-xs-right':!nope.isIframe}" ui-view="content"></div>
  </div>
</div>
