#!/bin/bash
set -e

# Configuration
PTERO_PATH="/var/www/pterodactyl"
THEME_REPO="https://github.com/tobi-ops-cpu/ptero-theme/archive/main.tar.gz"
THEME_DIR="ptero-theme-main"

# Try normal install first
echo "📦 Installing dependencies..."
npm install --silent

# If it fails, retry with legacy peer deps
if [ $? -ne 0 ]; then
    echo "🔄 Retry with legacy peer deps..."
    npm install --legacy-peer-deps --silent
fi

# Build assets
echo "⚙️ Building assets..."
npm run build:production

# Create backup directory with timestamp
BACKUP_DIR="$PTERO_PATH/theme-backups/$(date +%F-%H-%M-%S)"
echo "🔹 Installing Pterodactyl Dark Nexus Theme..."
mkdir -p "$BACKUP_DIR"

# Backup original theme files
echo "💾 Backing up current resources to: $BACKUP_DIR"
cp -r "$PTERO_PATH/resources/views" "$BACKUP_DIR/views.bak"
cp -r "$PTERO_PATH/resources/css" "$BACKUP_DIR/css.bak"
cp -r "$PTERO_PATH/resources/scripts" "$BACKUP_DIR/scripts.bak"

# Download and extract theme
echo "📥 Downloading latest version..."
wget -qO- "$THEME_REPO" | tar xz
if [ ! -d "$THEME_DIR" ]; then
    echo "❌ Failed to download or extract theme. Check your internet connection or GitHub URL."
    exit 1
fi

# Apply theme files
echo "🔄 Applying custom theme files..."

# Apply Blade templates
if [ -d "$THEME_DIR/resources/views" ]; then
    cp -r "$THEME_DIR/resources/views/"* "$PTERO_PATH/resources/views/"
    echo "   ✔️ Applied Blade templates"
fi

# Apply CSS
if [ -d "$THEME_DIR/resources/css" ]; then
    cp -r "$THEME_DIR/resources/css/"* "$PTERO_PATH/resources/css/"
    echo "   ✔️ Applied CSS styles"
fi

# Apply JavaScript/Vue
if [ -d "$THEME_DIR/resources/scripts" ]; then
    cp -r "$THEME_DIR/resources/scripts/"* "$PTERO_PATH/resources/scripts/"
    echo "   ✔️ Applied Vue components and scripts"
fi

# Cleanup downloaded archive
rm -rf "$THEME_DIR"

# Rebuild panel assets
echo "⚙️ Compiling assets (this may take a moment)..."
cd "$PTERO_PATH"
npm ci --silent || npm install --silent
npm run build:production

# Final success message
echo ""
echo "✅ Theme installed successfully!"
echo "💡 Clear your browser cache and refresh the panel."
echo "📎 Backup location: $BACKUP_DIR"
echo ""
echo "👋 Thank you for using Dark Nexus Theme!"
