import config from '../../../config.json';

export default class authen {
  static isAuthenticated() {
    return new Promise(async resolve => {
      let user = this.getUser();

      if (user) {
        resolve(user);
      }
  
      let token = this.getToken();
      
      if (token !== null){
        let res = await fetch(`${config['serverhost']}/users/authen`, {
          method: 'GET',
          headers: {
            'Authorization': token
          }
        });

        //console.log(await res.text());
        user = await res.json();
        console.log(user);

        if (user){
          delete user['PASSWORD'];
          window.sessionStorage.setItem(config['local_user'], JSON.stringify(user));
          resolve(user);
        }
        else {
          resolve(false);
        }
      }
      
      resolve(false);
    });
  }

  static Logout() {
    if (this.getUser()){
      window.sessionStorage.removeItem(config['local_user']);
      window.localStorage.removeItem(config['token_name']);
      window.location.reload(true);
    }
  }

  static getToken() {
    return window.localStorage.getItem(config['token_name']);
  }
  static saveToken(token) {
    window.localStorage.setItem(config['token_name'], token);
  }

  static getUser() {
    let user = window.sessionStorage.getItem(config['local_user']);

    return user !== null ? JSON.parse(user) : null;
  }
}