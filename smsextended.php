<?php

require_once 'smsextended.civix.php';
use CRM_Smsextended_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function smsextended_civicrm_config(&$config) {
  _smsextended_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function smsextended_civicrm_xmlMenu(&$files) {
  _smsextended_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function smsextended_civicrm_install() {
  CRM_Core_DAO::executeQuery("
    ALTER TABLE civicrm_phone
    ADD COLUMN is_sms tinyint(4) DEFAULT '0' COMMENT 'Is this for SMS?'
  ");
  _smsextended_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function smsextended_civicrm_postInstall() {
  _smsextended_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function smsextended_civicrm_uninstall() {
  _smsextended_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function smsextended_civicrm_enable() {
  _smsextended_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function smsextended_civicrm_disable() {
  _smsextended_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function smsextended_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _smsextended_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function smsextended_civicrm_managed(&$entities) {
  _smsextended_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function smsextended_civicrm_caseTypes(&$caseTypes) {
  _smsextended_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function smsextended_civicrm_angularModules(&$angularModules) {
  _smsextended_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function smsextended_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _smsextended_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function smsextended_civicrm_entityTypes(&$entityTypes) {
  $entityTypes['CRM_Core_DAO_Phone']['fields_callback'][]
    = function ($class, &$fields) {
      $fields['is_sms'] = _getSMSField();
    };
  _smsextended_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function smsextended_civicrm_themes(&$themes) {
  _smsextended_civix_civicrm_themes($themes);
}

function _getMobileKey($form) {
  $mobileTypeID = CRM_Core_PseudoConstant::getKey('CRM_Core_BAO_Phone', 'phone_type_id', 'Mobile');
  if (empty($mobileTypeID)) {
    return NULL;
  }
  //Check if any mobile field is present on the form.
  foreach ($form->_elementIndex as $key => $val) {
    if (strpos($key, 'phone-') === 0 && CRM_Utils_String::endsWith($key, "-{$mobileTypeID}")) {
      return $key;
    }
  }
  return NULL;
}

/**
 * Define SMS field.
 */
function _getSMSField() {
  return [
    'name' => 'is_sms',
    'type' => CRM_Utils_Type::T_BOOLEAN,
    'title' => ts('Use for SMS'),
    'description' => ts('Is this used for SMS?'),
    'where' => 'civicrm_phone.is_sms',
    'default' => '0',
    'table_name' => 'civicrm_phone',
    'entity' => 'Phone',
    'bao' => 'CRM_Core_BAO_Phone',
    'localizable' => 0,
    'add' => '5.0',
    'html' => [
      'type' => 'CheckBox'
    ]
  ];
}

/**
 * Implements hook_civicrm_buildForm().
 */
function smsextended_civicrm_buildForm($formName, &$form) {
  if ($form->elementExists('is_sms')) {
    $mobileKey = _getMobileKey($form);
    if ($mobileKey && $form->elementExists($mobileKey)) {
      $contactID = $form->getContactId();
      if (!empty($contactID)) {
        list(, $locType, ) = explode('-', $mobileKey);
        $phone = civicrm_api3('Phone', 'get', [
          'sequential' => 1,
          'return' => ["is_sms"],
          'contact_id' => $contactID,
          'phone_type_id' => "Mobile",
          'location_type_id' => $locType,
        ]);
        if (!empty($phone['values'][0]['is_sms'])) {
          $defaults['is_sms'] = TRUE;
          $form->setDefaults($defaults);
        }
      }
    }
    else {
      //Remove Element if no mobile type phone is added.
      $form->removeElement('is_sms');
    }
  }

  if ($formName == 'CRM_Contact_Form_Inline_Phone') {
    $totalBlocks = 6;
    for ($blockId = 1; $blockId < $totalBlocks; $blockId++) {
      $form->addElement('advcheckbox', "phone[$blockId][is_sms]", NULL, '');
    }
  }

  if ($formName == 'CRM_Contact_Form_Contact') {
    $blockId = ($form->get('Phone_Block_Count')) ? $form->get('Phone_Block_Count') : 1;

    $form->addElement('advcheckbox', "phone[$blockId][is_sms]", NULL, '');
  }
}

/**
 * Save is_sms field in session to retrieve it in pre hook.
 */
function smsextended_civicrm_postProcess($formName, $form) {
  if ($form->elementExists('is_sms')) {
    $mobileKey = _getMobileKey($form);
    if ($mobileKey && $form->elementExists($mobileKey)) {
      $params = $form->getVar('_params');
      $mobilePhoneValue = $params[$mobileKey] ?? $form->_submitValues[$mobileKey];
      if (!empty($mobilePhoneValue)) {
        $contactID = $form->getContactId();
        $session = CRM_Core_Session::singleton();
        $sessionKey = "{$contactID}-{$mobileKey}";
        if (!empty($params['is_sms']) || !empty($form->_submitValues['is_sms'])) {
          $session->set($sessionKey, TRUE);
        }
        else {
          $session->set($sessionKey, FALSE);
        }
      }
    }
  }
}

/**
 * Implements hook_civicrm_pre().
 */
function smsextended_civicrm_pre($op, $objectName, $id, &$params) {
  if ($objectName == 'Phone' && !empty($params['phone_type_id']) && $op != 'delete') {
    $mobileTypeID = CRM_Core_PseudoConstant::getKey('CRM_Core_BAO_Phone', 'phone_type_id', 'Mobile');
    if ($params['phone_type_id'] == $mobileTypeID) {
      $sessionKey = "{$params['contact_id']}-phone-{$params['location_type_id']}-{$mobileTypeID}";
      $session = CRM_Core_Session::singleton();
      $params['is_sms'] = $session->get($sessionKey) ? TRUE : FALSE;
    }
  }

  // if ($objectName == 'Phone' && !empty($params['phone_type_id'])) {
  //   $mobileType = CRM_Core_PseudoConstant::getKey('CRM_Core_DAO_Phone', 'phone_type_id', 'Mobile');
  //   $params['is_sms'] = $params['phone_type_id'] == $mobileType ? TRUE : FALSE;
  // }
}

/**
 * Add is sms field to the profile.
 */
function smsextended_civicrm_alterUFFields(&$fields) {
  $fields['Contact']['is_sms'] = _getSMSField();
}

/**
 * Implements hook_civicrm_alterMailingRecipients().
 */
function smsextended_civicrm_alterMailingRecipients(&$mailingObject, &$criteria, $context) {
  //Return if mailing is not an SMS.
  if (CRM_Utils_System::isNull($mailingObject->sms_provider_id)) {
    return;
  }

  if ($context == 'pre') {
    //Do not consider Bulk 'Email' Opt Out setting for SMS.
    unset($criteria['is_opt_out']);

    //Included recipients should have is_sms = 1 for the mobile number.
    $criteria['phone_is_sms'] = CRM_Utils_SQL_Select::fragment()->where("civicrm_phone.is_sms = 1");
  }
  elseif ($context == 'post') {
    //If a single contact has multiple phone enabled for "is_sms"
    //include those numbers in the recipients list.
    $currentRecipients = civicrm_api3('MailingRecipients', 'get', [
      'sequential' => 1,
      'mailing_id' => $mailingObject->id,
      'options' => ['limit' => 0],
    ]);
    foreach ($currentRecipients['values'] as $recipients) {
      $smsPhoneIds = civicrm_api3('Phone', 'get', [
        'sequential' => 1,
        'is_sms' => 1,
        'contact_id' => $recipients['contact_id'],
        'id' => ['NOT IN' => [$recipients['phone_id']]],
        'options' => ['limit' => 0],
      ]);
      if (!empty($smsPhoneIds['count'])) {
        foreach ($smsPhoneIds['values'] as $phone) {
          $params = [
            1 => [$mailingObject->id, 'Integer'],
            2 => [$phone['contact_id'], 'Integer'],
            3 => [$phone['id'], 'Integer'],
          ];
          CRM_Core_DAO::executeQuery("
            INSERT IGNORE INTO civicrm_mailing_recipients (mailing_id, contact_id, phone_id)
            VALUES (%1, %2, %3)", $params);
        }
      }
    }
  }
}


// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 *
function smsextended_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 *
function smsextended_civicrm_navigationMenu(&$menu) {
  _smsextended_civix_insert_navigation_menu($menu, 'Mailings', array(
    'label' => E::ts('New subliminal message'),
    'name' => 'mailing_subliminal_message',
    'url' => 'civicrm/mailing/subliminal',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _smsextended_civix_navigationMenu($menu);
} // */
