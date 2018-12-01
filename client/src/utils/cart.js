import config from '../../../config.json';
import { Observable } from './Observable';

class CartService extends Observable {
  updateItem(username, product, quantity){
    let items = this.getItems(username);

    let key = product['PRODUCT_ID'];

    if (key in items){
      quantity = items[key].quantity + quantity;
    }

    items[key] = { quantity };

    this.saveItems(username, items);
    this.publish('cart_count', this.countItems(username));
  }

  removeItem(username, id) {
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
    this.publish('cart_update');
  }
}

const cart = new CartService();

export default cart;