import {Component, Input} from '@angular/core';
import {FormsModule} from "@angular/forms";
import {PaginatorModule} from "primeng/paginator";
import {NgClass} from "@angular/common";

@Component({
  selector: 'app-filter-bar',
  standalone: true,
    imports: [
        FormsModule,
        PaginatorModule,
        NgClass
    ],
  templateUrl: './filter-bar.component.html',
  styleUrl: './filter-bar.component.scss'
})
export class FilterBarComponent {
    @Input() isAhead: boolean = false;
}
