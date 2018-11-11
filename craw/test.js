const puppeteer = require('puppeteer');
const fs = require('fs');

console.log(JSON.parse(fs.readFileSync(`${__dirname}/craw_data/details/apple-ipad/ipad-3-16gb-wifi-4g-p1248.json`, 'utf-8')));
// (async () => {
//   const browser = await puppeteer.launch();

//   const page = await browser.newPage();
//   await page.goto('https://didongthongminh.vn/asus-zenfone-4-a400-pin-1600-mah-p1006');

//   const selectedColor = await page.$('.tawcvs-swatches .selected');
//   const selected = await page.evaluate(el => el.getAttribute('data-value'), selectedColor);
  
//   console.log(selected);

//   const colors = await page.$$('.tawcvs-swatches .swatch');
//   for(const color of colors){
//     await color.click();
//     const image = await page.waitForSelector('img.wp-post-image');
//     const value = await page.evaluate(el => el.getAttribute('data-value'), color);
//     console.log(value);
//   }
//   await browser.close();
// })();


