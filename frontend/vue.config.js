module.exports = {
  "devServer": {
    "proxy": "http://groupware.local"
  },
  "outputDir": "../public",
  "indexPath": process.env.NODE_ENV === 'production'
      ? '../resources/views/index.blade.php'
      : 'index.html',
  "transpileDependencies": [
    "vuetify"
  ]
}