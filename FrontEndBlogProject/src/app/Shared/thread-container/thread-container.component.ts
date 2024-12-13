import {Component, Input} from '@angular/core';
import {environment} from "../../_environments/ApiUrlEnvironment";
import {DomSanitizer} from "@angular/platform-browser";
import {RouterLink, RouterOutlet} from "@angular/router";
import {Threads} from "../../Interfaces/ReturnInterfaces";
import {NgClass} from "@angular/common";

@Component({
  selector: 'app-thread-container',
  standalone: true,
  imports: [
    RouterLink,
    NgClass
  ],
  templateUrl: './thread-container.component.html',
  styleUrl: './thread-container.component.scss'
})
export class ThreadContainerComponent {
  @Input() onList:boolean = false;

  public constructor(private sanitizer: DomSanitizer, private router: RouterOutlet) {
  }
  apiUrl: string = environment.apiUrl;
  @Input() thread?:Threads;



  getSanitizedContent(htmlContent: string) {

    return this.sanitizer.bypassSecurityTrustHtml(htmlContent);

  }
}
