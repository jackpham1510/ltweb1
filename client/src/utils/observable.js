export class Subject {
  job = null;
  observes = [];

  constructor(job){
    this.job = job;
  }

  publish = (data) => {
    for(let obs of this.observes){
      obs(data);
    }
  }

  subscribe = (obs) => {
    this.observes.push(obs);
  }
}