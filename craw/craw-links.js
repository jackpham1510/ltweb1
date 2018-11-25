const puppeteer = require('puppeteer');
const fs = require('fs');

const crawLinks = async (url, browser) => {
  const page = await browser.newPage();
  await page.goto(url);
  console.log(`> Start craw links from ${url}...`);

  const links = await page.evaluate(async () => {
    //document.querySelector('#sb-infinite-scroll-load-more-1 a').click();
    //await new Promise(res => setTimeout(res, 5000));
    let _links = [];
    let _linkEls = document
    .querySelectorAll('.dst_dsco .woocommerce-LoopProduct-link.woocommerce-loop-product__link')
    _linkEls.forEach((_linkEl, idx) => {
      //if (idx >= 8) return;
      let href = _linkEl.getAttribute('href');
      let parentQ = `a[href="${href}"]`;
      let parsePrice = s => parseInt(s.split('&')[0].replace(/,/g,''));
      let getPath = s => s.split('/').reverse()[0];
      let primtitle = document.querySelector(`${parentQ} .dst_primtitle`);
      let subtitle = document.querySelector(`${parentQ} .dst_subtitle`);
      let price = document.querySelector(`${parentQ} .woocommerce-Price-amount.amount`);
      //let image = document.querySelector(`${parentQ} .dst_lprdc>img`);
      _links.push({
        url: href,
        path: getPath(href),
        //imageUrl: image.getAttribute('data-original'),
        primtitle: primtitle.innerHTML,
        subtitle: subtitle ? subtitle.innerHTML : '',
        price: parsePrice(price.innerHTML), 
      });
    });
    return _links;   
  });

  console.log(`> Craw success ${links.length} items from ${url}!`);
  return links;
}

(async () => {
  const browser = await puppeteer.launch();
  //['iphone', 'samsung', 'xiaomi', 'oppo', 'asus']
  ['meizu']
  .forEach(async path => {
    const url = 'https://didongthongminh.vn/' + path;
    const filename = `${path}-links.json`; 
    const links = await crawLinks(url, browser);
    console.table(links);
    fs.writeFileSync(`${__dirname}/craw_data/links/${filename}`, JSON.stringify(links));
    console.log(`> Export links from ${url} to ${filename} success!`);
  });
})();

module.exports = crawLinks;