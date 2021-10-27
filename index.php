<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
use Bitrix\Main\Page\Asset;
Asset::getInstance()->addString('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">');
Asset::getInstance()->addString('<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>');
?>
<div class="container mb-5 mt-5">
	<h1><?$APPLICATION->ShowTitle(false)?></h1>

	<?$APPLICATION->IncludeComponent(
		"vicks:iblock.list",
		"",
		Array(
			"CACHE_TIME" => "3600",
			"CACHE_TYPE" => "A",
			"IBLOCK_ID" => "1",
			"IBLOCK_TYPE" => "section",
			"PAGER" => "4"
		)
	);?>

</div>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>
