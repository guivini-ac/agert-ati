#!/usr/bin/env bash
set -e

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
VENDOR_DIR="$ROOT_DIR/assets/vendor"

BOOTSTRAP_VERSION="5.3.0"
ICONS_VERSION="1.11.0"

mkdir -p "$VENDOR_DIR/bootstrap" "$VENDOR_DIR/bootstrap-icons/fonts" "$VENDOR_DIR/poppins"

curl -L -o "$VENDOR_DIR/bootstrap/bootstrap.min.css" "https://cdn.jsdelivr.net/npm/bootstrap@${BOOTSTRAP_VERSION}/dist/css/bootstrap.min.css"
curl -L -o "$VENDOR_DIR/bootstrap/bootstrap.bundle.min.js" "https://cdn.jsdelivr.net/npm/bootstrap@${BOOTSTRAP_VERSION}/dist/js/bootstrap.bundle.min.js"

curl -L -o "$VENDOR_DIR/bootstrap-icons/bootstrap-icons.css" "https://cdn.jsdelivr.net/npm/bootstrap-icons@${ICONS_VERSION}/font/bootstrap-icons.css"
curl -L -o "$VENDOR_DIR/bootstrap-icons/fonts/bootstrap-icons.woff" "https://cdn.jsdelivr.net/npm/bootstrap-icons@${ICONS_VERSION}/font/fonts/bootstrap-icons.woff"
curl -L -o "$VENDOR_DIR/bootstrap-icons/fonts/bootstrap-icons.woff2" "https://cdn.jsdelivr.net/npm/bootstrap-icons@${ICONS_VERSION}/font/fonts/bootstrap-icons.woff2"

curl -L -o "$VENDOR_DIR/poppins/poppins.css" "https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"

(
  cd "$VENDOR_DIR/poppins"
  for url in $(grep -o 'https://fonts.gstatic.com[^)]*' poppins.css); do
    fname=$(basename "$url")
    curl -L -o "$fname" "$url"
    sed -i "s@$url@$fname@g" poppins.css
  done
)

echo "Assets fetched in $VENDOR_DIR"
