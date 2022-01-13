let ftp = require('basic-ftp')

async function deploy (done) {
  let config, client, files

  config = {
    host: '31.31.198.122',
    user: 'u1559320',
    password: 'tkjrv6X7Ju11eAcD',
  }

  client = new ftp.Client()
  await client.access(config)
  await client.cd('www/gergewebdev.ru')
  
  files = await client.list()
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