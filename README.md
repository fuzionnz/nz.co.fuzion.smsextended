# Extend SMS Support

![Screenshot](/images/use_for_sms.jpg)

- Adds a new field `Use for SMS` for Mobile type phone numbers. 
- If checked, it is preferred over any other mobile(primary, etc) for sending SMS to the contact. 
- If a contact has 2 mobile numbers with this setting enabled, both numbers will receive the SMS.
- The field is available to use in drupal webform. Just create a new drupal field with radio options Yes/No and the form key is set to `civicrm_1_contact_1_phone_is_sms`.
- Currently in civi, if person sets 'Opt Out' on email it also opts them out of SMS. This ext override that setting and will still send the SMS if `Use for SMS` is enabled.
- It respects the `Do Not SMS` core field setting and will not send the SMS if it is enabled on the contact.

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP v7.0+
* CiviCRM 

## Installation (Web UI)

This extension has not yet been published for installation via the web UI.

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl nz.co.fuzion.smsextended@https://github.com/fuzionnz/nz.co.fuzion.smsextended/archive/master.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/fuzionnz/nz.co.fuzion.smsextended.git
cv en smsextended
```

## Usage

After installation, 

- Navigate to the any contact summary page
- Add/Edit a phone number.
- Select Phone Type = Mobile, a new field will be displayed below to enable `Use for SMS`. 
- This number will be used for sending SMS to the contact.
