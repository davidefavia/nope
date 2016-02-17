<div class="container-fluid">
  <div class="row">
    <div id="sidebar" class="col col-md-2 col-sm-3" ng-if="!nope.isIframe">
      <div id="welcome">
        <a href="#/user/view/{{currentUser.id}}">
          <img class="img-circle" ng-src="{{currentUser.cover.preview.profile || assetsPath + 'assets/img/nope.png'}}" />
        </a>
        <p>
          <a ng-href="#/user/view/{{currentUser.id}}">{{currentUser.getFullName()}}</a> |
          <a href="<?php echo \Nope\Utils::getFullBaseUrl(); ?>" target="_blank"><i class="fa fa-home"></i></a>
        </p>
      </div>
      <div class="nav-container">
        <ul class="nav">
          <?php foreach ($menuItems as $key => $item) { ?>
            <li id="menu-item-<?php echo $item['id']; ?>" <?php if($item['activeWhen']) { ?>ng-class="{active:<?php echo $item['activeWhen']; ?>}"<?php }?> <?php if($item['permission']) { echo 'nope-can="'.$item['permission'].'"';} ?> <?php if($item['role']) { echo 'nope-role="'.$item['role'].'"';} ?>>
              <a <?php foreach($item['attrs'] as $key => $attr) { echo "$key=\"$attr\" ";} ?>><i class="<?php echo $item['icon']; ?>"></i> <?php echo $item['label']; ?></a>
            </li>
          <?php } ?>
        </ul>
      </div>
    </div>
    <div id="main" class="col" ng-class="{'col-md-10 col-md-offset-2 col-sm-9 col-sm-offset-3':!nope.isIframe}" ui-view="content"></div>
  </div>
</div>
