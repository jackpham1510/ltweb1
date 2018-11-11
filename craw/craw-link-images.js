const puppeteer = require('puppeteer');
const fs = require('fs');

const jsons = fs.readdirSync(`${__dirname}/craw_data/links`);

(async () => {
  const browser = await puppeteer.launch();
  
  for(const json of jsons){
    const data = fs.readFileSync(`${__dirname}/craw_data/links/${json}`, 'utf-8');
    const imageFolder = json.split('-')[0];
    const links = JSON.parse(data);
    
    let c = 0;
    for(let item of links){
      const page = await browser.newPage();
      await page.goto(item.imageUrl);
      await page.evaluate(() => {
        document.body.style.background = '#ffffff';
      });
      const el = await page.waitForSelector('img');
      await el.screenshot({
        path: `craw_data/images/links/${imageFolder}/${item.path}.png`
      });
      console.log(`${++c}. Craw image: "${item.path}.png" success!`);
      if (c === links.length){
        console.log('---------------------------------------------------------------------------------')
      }
    }
  }
})();