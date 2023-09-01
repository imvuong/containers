#!/bin/bash

touch /opt/bitnami/mediawiki/LocalSettingsExtra.php

echo -e "\n# Load extra settings\nif (file_exists(\"LocalSettingsExtra.php\")) {\n    require \"LocalSettingsExtra.php\";\n}" >> /bitnami/mediawiki/LocalSettings.php
