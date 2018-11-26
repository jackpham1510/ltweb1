import config from '../../../config.json';
import { Observable } from './Observable';

class CartService extends Observable {
  async updateItem(username, category, branch, product, quantity, color){
    let items = this.getItems(username);

    let key = product['PRODUCT_ID']+'_'+color;

    if (key in items){
      quantity = items[key].quantity + quantity;
    }

    items[key] = { 
      quantity, color, category, branch, 
      id: product['PRODUCT_ID'],
      productName: product['NAME'],
      url: product['URL'],
      price: product['PRICE'],
      image: product['DETAIL'].images.items[color] 
    };

    //console.log('update-cart', items);

    this.saveItems(username, items);
    this.publish('cart_count', this.countItems(username));
  }

  async removeItem(username, id) {
    let items = this.getItems(username);

    delete items[id];

    this.saveItems(username, items);
    this.publish('cart_count', this.countItems(username));
    this.publish('cart_update');
  }

  getItems(username){
    let items = localStorage.getItem(config['local_cart']+'_'+username);

    items = items !== null ? JSON.parse(items) : {};

    return items;
  }

  saveItems(username, items){
    localStorage.setItem(config['local_cart']+'_'+username, JSON.stringify(items));
  }

  countItems(username){
    let items = this.getItems(username);
    let sum = 0;
    for (let key in items) {
      sum += items[key].quantity;
    }
    return sum;
  }

  clear(username){
    localStorage.removeItem(config['local_cart']+'_'+username);
    this.publish('cart_count', 0);
  }
}

const cart = new CartService();

export default cart;