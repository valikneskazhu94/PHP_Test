'use strict';

class Elements {
  constructor() { 
    this.Run.bind(this);

    this.Run(); 
  }

  Run() {
    this.filter = {
      parent: document.querySelector('.filter'),
      id: document.querySelector('.filter input:first-child'),
      city: document.querySelector('.filter input:nth-child(2)'),
      summ: document.querySelector('.filter select'),
      button: document.querySelector('.filter button')
    };
    this.table = {
      parent: document.querySelector('.orders'),
      head: document.querySelector('.orders thead'),
      body: document.querySelector('.orders tbody')
    };
  }
}

class Filter {
  constructor(dom) {
    this.dom = dom;
    this.Run.bind(this);
  }

  async Run (table) {
    const data = {id: this.dom.id.value, city: this.dom.city.value, summ: this.dom.summ.value};
    const result = await Fetch.DoFilter(data);
    console.log(result);
  }
}

class Fetch {
  static async DoFilter(raw) {
    console.log(raw);
    const headers = new Headers();
    headers.append('Content-Type', 'application/x-www-form-urlencoded');

    const params = {
      method: 'POST',
      headers: headers,
      body: raw
    }

    const response = await fetch(`http://localhost/api`, params);
    let data = null;
    
    if (response.status === 200)
      data = await response.json();

    return data;
  }
}

class Runtime {
  constructor() {
    this.Run.bind(this);
    this.Listen.bind(this);
  }

  Run() {
    this.elements = new Elements();
    this.filter = new Filter(this.elements.filter);

    console.log(this.elements);
    this.Listen();
  }

  Listen() {
    this.elements.filter.button.addEventListener('click', () => this.filter.Run(this.elements.table));
  }
}

(new Runtime()).Run();