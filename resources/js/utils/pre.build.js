const fs = require('fs-extra')
const path = require('path')

try {
  fs.removeSync(path.resolve(__dirname, '../../../public/js'))
  fs.removeSync(path.resolve(__dirname, '../../../public/images'))
  fs.removeSync(path.resolve(__dirname, '../../../public/mix-manifest.json'))
}
catch (error) {
  console.log('Error Deleting /public/js and /public/mix-manifest --> ', error.message);
}