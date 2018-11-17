import config from '../../../config.json';

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
  self.resolveProductImg = function (product, categories, branchs){
    let cid = product['CATEGORY_ID'];
    let bid = product['BRANCH_ID'];
    let imgs = product['DETAIL']['images'];
    let imgUrl = imgs.items[imgs.selected];

    let result = categories[cid]['URL'] + '/';
    if (bid !== null){
      result += branchs[bid]['URL'] + '/';
    }
    return result + imgUrl;
  }
  self.formatMoney = function (m){
    return m.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
  }

  self.fetchProduct = async (path, cb) => {
    let res = await fetch(`${config.serverhost}/product/${path}`);
    let data = await res.json();
    data = data.map(item => ({...item, DETAIL: JSON.parse(item['DETAIL'])}));
    cb(data);
  }

})();