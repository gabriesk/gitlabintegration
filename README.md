<div align="center">
    <img src="https://raw.githubusercontent.com/gabriesk/gitlabintegration/master/img/glpi_logo.png" width="300px"/>
</div>

#

<p align="center">
  <img alt="Release version" src="https://img.shields.io/github/v/release/gabriesk/gitlabintegration">
   <img alt="Repository size" src="https://img.shields.io/github/repo-size/gabriesk/gitlabintegration">
  <img alt="Open issues" src="https://img.shields.io/github/issues-raw/gabriesk/gitlabintegration">
  <img alt="Last commit on GitHub" src="https://img.shields.io/github/last-commit/gabriesk/gitlabintegration">
  <img alt="GitHub license" src="https://img.shields.io/github/license/gabriesk/gitlabintegration">
  <img alt="Author Gabriel" src="https://img.shields.io/badge/author-Gabriel Nascimento dos Passos-blue">
</p>

**note: This fork aims to continue from where the original creator, [Fáiza Letícia](https://github.com/faizaleticia), left off five years ago. All credit should be directed to her. My primary goal here is to support the community and improve this tool. 



# Gitlab Integration

Gitlab Integration is a plugin to use into GLPI - Gestionnaire Libre de Parc Informatique - when a ticket needs to integrate with Gitlab.

## Installation

Clone this repository into the ```plugins``` folder of your GLPI installation.

Configure the parameters for GitLab integration:
  - Rename the file ```gitlabintegration.ini.example``` to ```gitlabintegration.ini```.
  - Change the values of the variables ```GITLAB_URL``` and ```GITLAB_TOKEN```:
    - ```GITLAB_URL```: specifies the URL to access the GitLab repository.
    - ```GITLAB_TOKEN```: specifies the GAT (Group Access Tokens) to access the GitLab repository.

After this, it is necessary to installand enable the plugin in ```Configuration/Plug-ins```.

## Giving Permissions to Users Profiles

To grant permissions,access the option ```Permissions Gitlab``` located under ```Administration```.

## Creating an Issue

To create an Issue, a ticket must be opened. Once the ticket is located, at the bottom of the ticket form, there is a field called ```Gitlab Project```. In this field, select the revelant Gitlab repo and then click on ```Create Issue```.

Once this is done, the issue will be successfully created on your GitLab repository!

#

## Known Issues

Converting this tool to GLPI version 10 was not an easy deed, but not overly complex either. There are still several rough edges, ~perhaps more than a few~, that I need to address in the near future. Nonetheless, I believe that while I continue refining this tool, it can already benefit others. Expect new updates soon.

That being said, here are the issues I have identified at the time of this commit:
- When in Debug Mode, GLPI will raise numerous warings during installion and when while using this Plug-In to create a Issue on GitLab. However, I didn't encounter any errors on GitLab's side, and expected behaviour seems correct;
- ```hook.php``` did sometimes create migrations that wouldn't work. If this continues happening, please let me know by opening a new issue.

 


