import {ChangeDetectorRef, Component, Injectable, OnInit,} from '@angular/core';
import { TitleServiceService } from '../../Services/title-service.service';
import { NavBarComponent } from '../../Shared/nav-bar/nav-bar.component';
import { CarouselComponent } from '../../Shared/carousel/carousel.component';
import { Router } from '@angular/router';
import { ThreadsService } from '../../Services/threads.service';
import {PaginatedThreads, Threads} from '../../Interfaces/ReturnInterfaces';
import { EditorModule } from 'primeng/editor';
import { FormBuilder, FormsModule, ReactiveFormsModule,  } from "@angular/forms";
import {ThreadContainerComponent} from "../../Shared/thread-container/thread-container.component";
import {PaginatorModule, PaginatorState} from 'primeng/paginator';
import {FilterBarComponent} from "../../Shared/filter-bar/filter-bar.component";



@Component({
  selector: 'app-home',
  standalone: true,
    imports: [
        NavBarComponent,
        CarouselComponent,
        EditorModule,
        FormsModule,
        ReactiveFormsModule,
        ThreadContainerComponent,
        PaginatorModule,
        FilterBarComponent
    ],
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {
  title = 'MusicBox Joinville Home';
  threads?: PaginatedThreads
  filteredThreads?: Array<Threads>;



  pageIndexThreads: number = 0;


  constructor(
      private titleService: TitleServiceService,
      private formBuilder: FormBuilder,
      private router: Router,
      private threadsService: ThreadsService,
      private cdr: ChangeDetectorRef,
        ) {
  }

  ngOnInit(): void {


    // Update the title
    this.titleService.updateTitle(this.title);

    this.fetchThreads(null);
  }




  fetchThreads(page: number | null) {
    this.threadsService.getAll(page).subscribe({
      next: (resp) => {
        this.threads = resp.data;
        this.filteredThreads = this.threads.dados?.filter(thread => thread.imagem !== null);
        //this.pageIndexThreads = (page ?? 1) - 1;
        this.cdr.detectChanges();
      },
      error: (err) => {
        console.error('Error fetching threads:', err);
      }
    });
  }


  onPageChange($event: PaginatorState) {
      if($event.page != null){
        this.pageIndexThreads = $event.page;
        this.fetchThreads($event.page+1)
      }
  }
}