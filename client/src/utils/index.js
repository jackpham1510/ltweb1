import config from '../../../config.json';
import { route } from 'preact-router';

export default new (function() {
  const self = this;

  self.flat = function (data, id){
    let result = {};
    if (Array.isArray(data)){
      for(let item of data){
        result[item[id]] = {};
        for(let key in item){
          if (key !== id){
            result[item[id]][key] = item[key];
          }
        }
      } 
    }
    return result;
  }

  self.chunk = function (arr, num){
    let result = [];
    if (num >= arr.length){
      return arr;
    }
    result[0] = [];
    for(let i = 0; i < arr.length; i++){
      let j = Math.floor(i/num);
      if (result[j] === undefined){
        result[j] = [];
      }
      result[j].push(arr[i]);
    }
    return result;
  }

  self.resolveFolder = function (product, categories, branchs){
    let cid = product['CATEGORY_ID'];
    let bid = product['BRANCH_ID'];
    let result = categories[cid]['URL'] + '/';
    result += (bid !== null ? branchs[bid]['URL'] : 'tat-ca') + '/';
    return result;
  }

  self.resolveProductImg = function (product, categories, branchs){;
    let imgs = product['DETAIL']['images'];
    let imgUrl = imgs.items[imgs.selected];
    return self.resolveFolder(product, categories, branchs) + imgUrl;
  }

  self.resolveProductUrl = function (product, categories, branchs){
    return '/san-pham/' + self.resolveFolder(product, categories, branchs) + product['URL'];
  }

  self.formatMoney = function (m){
    if (m === null){
      return "Liên hệ";
    }
    return m.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") + '₫';
  }

  self.fetch = async (path, cb, options = {}) => {
    let res = await fetch(`${config.serverhost}/${path}`, options);
    let data = await res.text();
    //console.log(data);
    cb(JSON.parse(data));
  }

  self.post = (path, body, cb) => {
    self.fetch(path, cb, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(body)
    });
  }

  self.fetchProduct = async (path, cb) => {
    self.fetch(`product/${path}`, res => {
      //console.log(res);
      if (!res) return null;

      if ('data' in res){
        res.data = res.data.map(item => {
          return ({...item, DETAIL: JSON.parse(item['DETAIL'])}) 
        });
      }
      else {
        res = res.map(item => {
          return ({...item, DETAIL: JSON.parse(item['DETAIL'])}) 
        });  
      }
      
      cb(res);
    });
  }

  self.parseUrl = (url) => {
    const query = url.split('?')[1];
    let params = {};
    if (query){
      for(let param of query.split('&')){
        const [key, value] = param.split('=');
        params[key] = value;
      }
    }
    return params;
  }

  self.scroll = (y = 0) => {
    let scrollItv = setInterval(function (){
      if (window.scrollY <= 0){
        clearInterval(scrollItv);
      }
      else {
        window.scrollBy(0, -(0.03 * window.innerHeight));
      }
    }, 1);
  }
  
  self.routeParams = (url, newParams) => {
    let currParams = self.parseUrl(url);

    let params = { ...currParams, ...newParams };
    url = url.split('?')[0] + '?';

    for(let key in params){
      url += `${key}=${params[key]}&`;
    }
    
    if (url[url.length - 1] === '&'){
      url = url.slice(0, -1);
    }

    route(url);
  }

  self.totalPrice = (items) => {
    return items.reduce((p, c) => (p + c['PRICE'] * c.quantity), 0);
  }
})();