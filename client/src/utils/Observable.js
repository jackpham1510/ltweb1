export class Observable {
  observes = {};

  subscribe(key, observe) {
    this.observes[key] = observe;
  }

  publishAll(data) {
    for (let key in this.observes) {
      this.observes[key](data);
    }
  }

  publish(key, data) {
    this.observes[key](data);
  }
}