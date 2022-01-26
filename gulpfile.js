let ftp = require('basic-ftp')

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

function testTask (done) {
  console.log("HELLO FROM GULP!!!!")
  done()
}

exports.default = testTask
exports.deploy = deploy
exports.sendDB = sendDBInter