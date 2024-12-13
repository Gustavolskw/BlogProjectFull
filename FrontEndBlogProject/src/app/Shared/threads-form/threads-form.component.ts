import { Router } from '@angular/router';
import {ChangeDetectorRef, Component, OnInit} from '@angular/core';
import {DatePipe, NgClass, NgOptimizedImage} from "@angular/common";
import {DomSanitizer, SafeHtml} from "@angular/platform-browser";
import {PaginatorModule} from "primeng/paginator";
import {EditorModule} from "primeng/editor";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {ThreadContainerComponent} from "../thread-container/thread-container.component";
import {UserService} from "../../Services/user.service";
import {TokenService} from "../../Services/token.service";
import {User} from "../../Interfaces/ReturnInterfaces";

@Component({
  selector: 'app-threads-form',
  standalone: true,
  imports: [
    DatePipe,
    NgOptimizedImage,
    PaginatorModule,
    EditorModule,
    FormsModule,
    ReactiveFormsModule,
    NgClass,
  ],
  templateUrl: './threads-form.component.html',
  styleUrl: './threads-form.component.scss'
})
export class ThreadsFormComponent  implements OnInit {
  titulo: string = "";
  descricao: string = "";
  autor:string = ""
  dataCriacao = new Date();
  image?:ArrayBuffer;
  user?:User|null;

  editorModules: any;

  constructor(private router: Router, private sanitizer: DomSanitizer,private cdr: ChangeDetectorRef, private userService:UserService, private tokenService:TokenService) { }

  ngOnInit() {

    if (this.tokenService.istokenActive()) {
      this.tokenService.authStatus$.subscribe((status) => {
        if (status) {
          this.fetchAuthUser();
        } else {
          this.user = null;
        }
        this.cdr.detectChanges();
      });
    }

  }

  private fetchAuthUser() {
    this.userService.user$.subscribe({
      next: (user) => {
        this.user = user;
        this.cdr.detectChanges();
      },
      error: (err) => this.tokenService.deleteToken()
    });
  }




  // Sanitize HTML content
  sanitizeHtml(html: string): SafeHtml {
    return this.sanitizer.bypassSecurityTrustHtml(html);
  }

  // Sanitize URLs
  sanitizeUrl(url: string): SafeHtml {
    return this.sanitizer.bypassSecurityTrustUrl(url);
  }

  // Sanitize resource URLs
  sanitizeResourceUrl(url: string): SafeHtml {
    return this.sanitizer.bypassSecurityTrustResourceUrl(url);
  }

  // Sanitize styles
  sanitizeStyle(style: string): SafeHtml {
    return this.sanitizer.bypassSecurityTrustStyle(style);
  }
}
