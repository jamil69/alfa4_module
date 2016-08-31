<?php

class alfa4_task extends CModule {

  public $MODULE_ID = "alfa4.task";
  public $MODULE_VERSION = "1.0.0";
  public $MODULE_VERSION_DATE = "26.08.2016";
  public $MODULE_NAME;
  public $MODULE_DESCRIPTION;
  
  public function alfa4_task() {

    $this->MODULE_NAME = "Модуль постановка задач";
    $this->MODULE_DESCRIPTION = "Управление задачами пользователя";

  }
  public function DoInstall() {
 
    $arProps = array(
		   "ENTITY_ID" => "USER",
		   "FIELD_NAME" => "UF_CHECK_DAY",
		   "XML_ID" => "UF_CHECK_DAY",
		   "USER_TYPE_ID" => "date",
		   "SORT" => 100,
		   "MULTIPLE" =>  "N",
		   "MANDATORY" => "N",
		   "SHOW_FILTER" => "N",
		   "SHOW_IN_LIST" => "Y",
		   "EDIT_IN_LIST" => "Y",
                   "EDIT_FORM_LABEL" => "Испытательный срок",
	           "LIST_COLUMN_LABEL" => "Испытательный срок",
		   "LIST_FILTER_LABEL" => "Испытательный срок"
     );

     $obUserField  = new CUserTypeEntity;
     $intID = $obUserField->Add($arProps, false);

     RegisterModuleDependences("main","OnAfterEpilog","alfa4.task","Alfa4TaskManager","setTaskForEmployee");
     RegisterModule("alfa4.task");
  }
 
  function DoUninstall() {

    UnRegisterModuleDependences("main","OnAfterEpilog","alfa4.task","Alfa4TaskManager","setTaskForEmployee");
    UnRegisterModule("alfa4.task");
  }
}
?>