import { Component, OnInit } from '@angular/core';
import {DomSanitizer, SafeHtml} from "@angular/platform-browser";
import { ActivatedRoute, Router } from "@angular/router";
import { ThreadsService } from "../../Services/threads.service";
import {Comment, Post, ThreadOne} from "../../Interfaces/ReturnInterfaces";
import { SweetAlertService } from "../../Services/sweet-alert.service";
import {DatePipe, NgOptimizedImage} from "@angular/common";
import {environment} from "../../_environments/ApiUrlEnvironment";

@Component({
  selector: 'app-thread',
  standalone: true,
  imports: [
    NgOptimizedImage,
    DatePipe
  ],
  templateUrl: './thread.component.html',
  styleUrl: './thread.component.scss'
})
export class ThreadComponent implements OnInit {
  constructor(private sanitizer: DomSanitizer,
    private router: Router,
    private threadsServcie: ThreadsService,
    private activatedRoute: ActivatedRoute,
    private sweetAlert: SweetAlertService) {
  }

  thread?: ThreadOne;
  commnets?:Array<Comment>
  posts?:Array<Post>
  errorMessage?:string;
  imgUrlApi:string = environment.apiThreadImageUrl;


  ngOnInit(): void {

    const id: number = Number(this.activatedRoute.snapshot.paramMap.get('id'));
    if (id) {
      this.threadsServcie.getSingleThread(id).subscribe({
        next: (resp) => {
          this.thread = {
            ...resp.data,
            dataCriacao: new Date(resp.data.dataCriacao) // Converte dataCriacao para Date
          };
          this.thread = resp.data;
          this.commnets = resp.data.comments;
          this.posts = resp.data.posts
          console.log(resp.data)
        },
        error: (resp) => {
          if (resp.status == 404) {
            this.sweetAlert.toastError(resp.error.message);
          }
          this.errorMessage = resp.error.message;

        }
      })
    }


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
