import {Component, OnInit} from '@angular/core';
import {NavBarComponent} from '../../Shared/nav-bar/nav-bar.component';

@Component({
  selector: 'app-historia',
  standalone: true,
  imports: [
    NavBarComponent
  ],
  templateUrl: './historia.component.html',
  styleUrl: './historia.component.scss'
})
export class HistoriaComponent implements OnInit {

  ngOnInit() {

  }
}
