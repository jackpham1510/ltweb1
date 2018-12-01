const fs = require('fs');

const insert = (table, fields, values) => {
  let insertSql = `insert into ${table}(${fields}) values(`;
  for(let value of values){
    insertSql += `${value},`
  }
  return insertSql.substring(0, insertSql.length - 1) + ');\n';
}

// let sql = '';
// const sqlFile = `${__dirname}/sql_script/insert_data.sql`;

// sql +=
//   insert('BRANCH', 'NAME, URL, VERSION', ["'Apple'", "'apple'", 0]) +
//   insert('BRANCH', 'NAME, URL, VERSION', ["'Samsung'", "'samsung'", 0]) +
//   insert('BRANCH', 'NAME, URL, VERSION', ["'Xiaomi'", "'xiaomi'", 0]) +
//   insert('BRANCH', 'NAME, URL, VERSION', ["'Oppo'", "'oppo'", 0]) +
//   insert('BRANCH', 'NAME, URL, VERSION', ["'Asus'", "'asus'", 0]);

// sql +=
//   insert('CATEGORY', 'NAME, URL, VERSION', ["'Điện thoại'", "'dien-thoai'", 0]) +
//   insert('CATEGORY', 'NAME, URL, VERSION', ["'Máy tính bảng'", "'may-tinh-bang'", 0]) +
//   insert('CATEGORY', 'NAME, URL, VERSION', ["'Tai nghe'", "'tai-nghe'", 0]) +
//   insert('CATEGORY', 'NAME, URL, VERSION', ["'Pin, sạc dự phòng'", "'pin-sac-du-phong'", 0]) +
//   insert('CATEGORY', 'NAME, URL, VERSION', ["'Dây cáp'", "'day-cap'", 0]) +
//   insert('CATEGORY', 'NAME, URL, VERSION', ["'Thẻ nhớ'", "'the-nho'", 0]);

// sql +=
//   insert('USERS', 'USERNAME, PASSWORD, NAME, TYPE, VERSION', ["'admin'", "'123456'", "'admin'", 1, 0]);


// fs.writeFileSync(sqlFile, sql);

// sql = '';

const linkJsons = fs.readdirSync(`${__dirname}/craw/craw_data/links`);
const category = (folder) => {
  switch (folder){
    case 'iphone':
    case 'samsung':
    case 'xiaomi':
    case 'oppo':
    case 'asus':
      return 1;
    case 'apple-ipad':
      return 2;
    case 'tai-nghe-loa-ngoai':
      return 3;
    case 'pin-dien-thoai-sac-du-phong':
      return 4;
    case 'cable-ket-noi':
      return 5;
    case 'the-nho-usb-flash':
      return 6;
  }
  return null;
}

const branch = (folder) => {
  switch (folder){
    case 'iphone':
    case 'apple-ipad':
      return 1;
    case 'samsung':
      return 2;
    case 'xiaomi':
      return 3;
    case 'oppo':
      return 4;
    case 'asus':
      return 5;
  }
  return null;
}

const insertProductSqlFile = `${__dirname}/sql_script/insert_product.sql`

fs.writeFileSync(insertProductSqlFile, '');

for(let linkJson of linkJsons){
  const links = JSON.parse(fs.readFileSync(`${__dirname}/craw/craw_data/links/${linkJson}`, 'utf8'));
  const folder = linkJson.split('-links')[0];

  //console.table(links);
  let sql = '';
  for(let link of links){
    const {path, primtitle, subtitle, price} = link;
    const details = fs.readFileSync(`${__dirname}/craw/craw_data/details/${folder}/${link.path}.json`, 'utf8');
    //console.log({path, primtitle, subtitle, details: details.length});
    sql += insert('PRODUCT',
      'BRANCH_ID, CATEGORY_ID, NAME, SUBTITLE, URL, PRICE, VIEW, SOLD, DETAIL, QUANTITY', [
      branch(folder), category(folder), `'${primtitle}'`, `'${subtitle}'`, `'${path}'`, price, 0, 0, `'${details}'`, 100
    ]);
  }
  fs.appendFileSync(insertProductSqlFile, sql);
}
