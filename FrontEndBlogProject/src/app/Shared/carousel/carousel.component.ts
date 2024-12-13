import {Component, Input, OnInit} from '@angular/core';
import {RouterLink} from '@angular/router';
import {NgClass, NgForOf, NgIf, NgOptimizedImage} from '@angular/common';
import {MatPaginatorModule} from '@angular/material/paginator';
import {Threads} from '../../Interfaces/ReturnInterfaces';
import {environment} from "../../_environments/ApiUrlEnvironment";

@Component({
  selector: 'app-carousel',
  standalone: true,
  imports: [
    RouterLink,
    NgOptimizedImage,
    MatPaginatorModule,
    NgForOf,
    NgIf,
    NgClass
  ],
  templateUrl: './carousel.component.html',
  styleUrl: './carousel.component.scss'
})
export class CarouselComponent implements OnInit {

  apiUrl: string = environment.apiThreadImageUrl;
  @Input() filteredThreads?: Array<Threads>;
  ngOnInit():void {
  }

}
