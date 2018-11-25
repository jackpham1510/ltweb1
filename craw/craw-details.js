const puppeteer = require('puppeteer');
const fs = require('fs');

function crawMainImages(page, item, folder){
  return new Promise(async res => {
    console.log(`> Start craw images from ${item.url}...`);
    const items = {};

    // try{
    //   const selectedColor = await page.waitForSelector('.tawcvs-swatches .selected', {
    //     timeout: 5000
    //   });
    //   const selected = await page.evaluate(el => el.getAttribute('data-value'), selectedColor);
    //   await selectedColor.click();
  
    //   await page.waitForSelector('.tawcvs-swatches .swatch', {
    //     timeout: 5000
    //   });
    //   const colors = await page.$$('.tawcvs-swatches .swatch');
      
    //   for(const color of colors){
    //       await color.click();
    //       const image = await page.waitForSelector('img.wp-post-image');
    //       const value = await page.evaluate(el => el.getAttribute('data-value'), color);
    //       const imageName = `${item.path}-${value}.png`
    //       await image.screenshot({
    //         path: `craw_data/images/details/${folder}/${imageName}`
    //       });
    //       console.log('\x1b[32m%s\x1b[37m', ` > Craw image: "${imageName}" success!`);
    //       items[value] = imageName;
    //   }
    //   res({selected, items});
    // } catch(err){
      console.log('\x1b[31m%s\x1b[37m', ` (!) This item dont't have colors picker!`);
      const image = await page.waitForSelector('img.wp-post-image');
      const imageName = `${item.path}.png`
      await image.screenshot({
        path: `craw_data/images/details/${folder}/${imageName}`
      });
      console.log('\x1b[32m%s\x1b[37m', ` > Craw image: "${imageName}" success!`);
      res({selected: 'default', items: {
        ['default']: imageName
      }});
    // }
  });
}

function crawDescription(page, item){
  return new Promise(async res => {
    console.log(`> Start craw description from ${item.url}...`);
    try{
      const desc = await page.$eval('.j_dr1 p', el => el.innerText);
      console.log('\x1b[32m%s\x1b[37m', ` > Craw description success!`);
      res(desc);
    }catch (err){
      console.log('\x1b[31m%s\x1b[37m', ` (!) This item don't have description!`);
      res('');
    }
  });
}

function crawSpecifications(page, item){
  return new Promise(async res => {
    console.log(`> Start craw specifications from ${item.url}...`);
    const ths = await page.$$eval('.shop_attributes th', ths => ths.map(th => th.innerText.trim()));
    const tds = await page.$$eval('.shop_attributes td', tds => tds.map(td => td.innerText.trim()));
    const table = ths.map((th, idx) => [th, tds[idx]]);
    console.log('\x1b[32m%s\x1b[37m', ` > Craw specifications success!`);
    res(table);
  });
}

const jsons = fs.readdirSync(`${__dirname}/craw_data/links`);
//console.log(item);

(async () => {
  const browser = await puppeteer.launch();

  for(const json of jsons){
    const data = fs.readFileSync(`${__dirname}/craw_data/links/${json}`, 'utf-8');
    const folder = json.split('-links')[0];
    const links = JSON.parse(data);

    
    if (folder === 'meizu') {
      for(let item of links){
        const page = await browser.newPage();
        await page.goto(item.url);
        console.log(`> Start details from ${item.url}...`);
  
        page.on('dialog', async dialog => {
          console.log('\x1b[31m%s\x1b[37m', `(!) Alert: ${dialog.message()}`);
          await dialog.dismiss();
        });
  
        const images = await crawMainImages(page, item, folder);
        const desc = await crawDescription(page, item);
        // const spec = await crawSpecifications(page, item);
        const spec = null;
        const detail = JSON.stringify({images, desc, spec});
        const filename = `${folder}/${item.path}.json`;
  
        fs.writeFileSync(`${__dirname}/craw_data/details/${filename}`, detail, 'utf-8');
        console.log('\x1b[33m%s\x1b[37m', `> Export details from ${item.url} to ${filename} success!`);
        await page.close();
      }    
    }
  }

  // console.log(images);
  // console.log(desc);
  // console.table(spec);
  
  await browser.close();
  console.log('> Done!');
})();