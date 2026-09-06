#!/usr/bin/env bash
set -euo pipefail

: "${META_ACCESS_TOKEN:?META_ACCESS_TOKEN is required}"
: "${META_FACEBOOK_PAGE_ID:?META_FACEBOOK_PAGE_ID is required}"
: "${META_INSTAGRAM_USER_ID:?META_INSTAGRAM_USER_ID is required}"

REMOTE_PATH="domains/amebazaar.in/public_html/wp-content/ame-social-secrets.php"

{
  printf '%s\n' '<?php'
  printf '%s\n' '/* Server-only AME Bazaar Meta credentials. Do not commit this file. */'
  printf "define('AME_META_ACCESS_TOKEN', %s);\n" "$(printf '%s' "$META_ACCESS_TOKEN" | php -r 'echo var_export(stream_get_contents(STDIN), true);')"
  printf "define('AME_META_FACEBOOK_PAGE_ID', %s);\n" "$(printf '%s' "$META_FACEBOOK_PAGE_ID" | php -r 'echo var_export(stream_get_contents(STDIN), true);')"
  printf "define('AME_META_INSTAGRAM_USER_ID', %s);\n" "$(printf '%s' "$META_INSTAGRAM_USER_ID" | php -r 'echo var_export(stream_get_contents(STDIN), true);')"
  printf '%s\n' "define('AME_META_GRAPH_VERSION', 'v25.0');"
} | ssh -p "${PORT}" -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null "${USERNAME}@${HOST}" \
  "cat > '${REMOTE_PATH}' && chmod 600 '${REMOTE_PATH}'"

echo "Meta social feed credentials provisioned server-side."
