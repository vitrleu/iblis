<?php
use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Iblock\Iblock;
use \Bitrix\Main\UI\PageNavigation;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class YourSimpleNewsComp extends CBitrixComponent
{
	/**
	 * Component constructor.
	 * @param CBitrixComponent | null $component
	 */
	public function __construct($component = null)
	{
		parent::__construct($component);
	}

	/**
	 * Check env end sets
	 * @return bool
	 * @throws Exception
	 */
	private function _check()
	{
		if (!Loader::includeModule('iblock')) {
			throw new \Exception('Не установлен модуль инфоблоков');
		}

		$this->entity = Iblock::wakeUp($this->arParams['IBLOCK_ID'])->getEntityDataClass();
		if (!$this->entity) {
			throw new \Exception('Инфоблок не найден');
		}

		if(!$this->arParams['PAGER']) {
			$this->arParams['PAGER'] = 4;
		}

		$nav = new PageNavigation('pager');
		$nav->setPageSize($this->arParams['PAGER'])
		   ->initFromUri();
		$this->nav = $nav;

		return true;
	}

	/**
	 * Prepare params
	 * @param $arParams
	 * @return mixed
	 */
	public function onPrepareComponentParams($arParams)
	{
		$request = Application::getInstance()->getContext()->getRequest();
		$arParams['YEAR'] = $request->get('year');
		$arParams['PAGE'] = $request->get('pager');
		return $arParams;
	}

     /**
     * Spam
     * @return CAllMain|CMain
     */
    private function _app() {
        global $APPLICATION;
        return $APPLICATION;
    }

	/**
	 * Request
	 * @return array
	 */
	private function getResult()
	{
		//Получить доступные годы
		$rcs = $this->entity::getList([
			'order'		=> ['ACTIVE_FROM' => 'desc'],
		    'select' 	=> ['ACTIVE_FROM'],
		    'filter' 	=> ['=ACTIVE' => 'Y']
		])->fetchAll();

		foreach ($rcs as $rc) {
			$years[] = $rc['ACTIVE_FROM']->format('Y');
		}
		
		$current = $this->arParams['YEAR'] ?? $years[0];

		$rcs = $this->entity::getList([
			'order'			=> ['ACTIVE_FROM' => 'desc'],
			'count_total'	=> true,
		    'filter' 		=> [
		    	'=ACTIVE'		=> 'Y',
		    	'><ACTIVE_FROM'	=> ['01.01.'.$current, '31.12.'.$current]
		    ],
		    'offset'		=> $this->nav->getOffset(),
		    'limit'			=> $this->nav->getLimit()
		]);

		while($rc = $rcs->fetch()) {
			$items[] = [
				'name'	=> $rc['NAME'],
				'date'	=> $rc['ACTIVE_FROM']->format('d.m.Y'),
				'img'	=> CFile::GetPath($rc['PREVIEW_PICTURE']),
				'text'	=> $rc['PREVIEW_TEXT']
			];
		}

		$result = [
			'years' 	=> array_unique($years),
			'items'		=> $items,
			'qty'		=> $rcs->getCount(),
			'current'	=> $current
		];

		return $result;
	}

	/**
	 * Runner
	 * @return void
	 */
	public function executeComponent()
	{
		$this->_check();

		if($this->StartResultCache()) {
			$this->arResult = $this->getResult();
			$this->includeComponentTemplate();
		}

		$this->nav->setRecordCount($this->arResult['qty']);

		$this->_app()->IncludeComponent(
			'bitrix:main.pagenavigation',
		   	'',
		   	["NAV_OBJECT" => $this->nav],
		   false
		);

		$this->_app()->SetTitle(Loc::getMessage('TITLE', ['QTY' => $this->arResult['qty']]));
	}
}
