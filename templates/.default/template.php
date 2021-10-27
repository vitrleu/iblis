<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<ul class="nav nav-tabs">
    <?php foreach($arResult['years'] as $year):?>
    <li class="nav-item">
        <a class="nav-link<?if($year == $arResult['current']):?> active" area-current="page<?endif?>" href="?year=<?=$year?>"><?=$year?></a>
    </li>
  <?php endforeach?>
</ul>

<div class="tab-content m-3">
    <?php foreach($arResult['items'] as $item):?>
    <div class="card mb-3">
        <div class="row g-0">
            <div class="col-md-4">
              <img src="<?=$item['img']?>" class="img-fluid rounded-start" alt="<?=$item['name']?>">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title"><?=$item['name']?></h5>
                    <p class="card-text"><?=$item['text']?></p>
                    <p class="card-text"><small class="text-muted"><?=$item['date']?></small></p>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach?>
</div>

<?=$arResult['nav']?>
