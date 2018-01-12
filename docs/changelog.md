---
layout: default
title: Changelog
---

All notable changes to `carpediem-mattermost-webhook` will be documented in this file.

{% for release in site.github.releases %}
## [{{ release.name }}]({{ release.html_url }}) - {{ release.published_at | date: "%Y-%m-%d" }}
{{ release.body | markdownify }}
{% endfor %}