# xcmtools

[![CircleCI](https://circleci.com/gh/greenpeace-cee/xcmtools.svg?style=svg)](https://circleci.com/gh/greenpeace-cee/xcmtools)

This CiviCRM extension makes various changes to the behaviour of [XCM](https://github.com/systopia/de.systopia.xcm).

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP v7.2+
* CiviCRM 5.19+

## Installation (Web UI)

This extension has not yet been published for installation via the web UI.

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl xcmtools@https://github.com/greenpeace-cee/xcmtools/archive/master.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/greenpeace-cee/xcmtools.git
cv en xcmtools
```

## Usage

### `Civi\Xcmtools\ApiWrapper\EmailLocationType`

When XCM is called with the `email` parameter, after a contact was matched or
created, this wrapper will change the location type of the email address to the
default location type configured in the relevant XCM profile.

## Known Issues

(* FIXME *)
