import { Injectable } from '@angular/core';
import { Title } from '@angular/platform-browser';

@Injectable({
  providedIn: 'root'
})
export class TitleServiceService {

  constructor(private titleService: Title) {}

  updateTitle(newTitle: string) {
    this.titleService.setTitle(newTitle);
  }

}
