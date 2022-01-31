let ftp = require('basic-ftp')
let fs = require('fs')

function configAxios (urlType = 'dev') {
  let configPath = './src/modules/testURL.json'
  let devURl = 'https://gergewebdev.ru/dbinter.php'
  let prodURL = 'dbinter.php'
  let confObj = {URL: devURl}
  let res

  if (urlType == 'dev') {confObj.URL = devURl}
  if (urlType == 'prod') {confObj.URL = prodURL}

  fs.writeFileSync(configPath, JSON.stringify(confObj), 'utf-8')
  res = fs.readFileSync(configPath, 'utf-8')
  return res
}

async function getConn () {
  let config, client

  config = {
    host: '31.31.198.122',
    user: 'u1559320',
    password: 'tkjrv6X7Ju11eAcD',
  }

  client = new ftp.Client()
  await client.access(config)
  
  return client
}

async function deploy (done) {
  let config, client, localPath, remotePath

  configAxios('prod')

  localPath = './dist'
  remotePath = 'www/gergewebdev.ru'
  config = {
    host: '31.31.198.122',
    user: 'u1559320',
    password: 'tkjrv6X7Ju11eAcD',
  }

  client = new ftp.Client()
  await client.access(config)
  await client.uploadFromDir(localPath, remotePath)

  console.log('uploadFinished!!')
  await client.close()
  configAxios('dev')
  done()
}

async function sendDBInter (done) {
  let config, client, files

  config = {
    host: '31.31.198.122',
    user: 'u1559320',
    password: 'tkjrv6X7Ju11eAcD',
  }

  client = new ftp.Client()
  await client.access(config)
  await client.cd('www/gergewebdev.ru')
  
  files = await client.uploadFrom('./dbinter.php', './dbinter.php')
  console.log(files)
  
  await client.close()
  done()
}


exports.deploy = deploy
exports.sendDB = sendDBInter