import {ChangeDetectorRef, Component, OnInit} from '@angular/core';
import {NavBarComponent} from "../../Shared/nav-bar/nav-bar.component";
import {FilterBarComponent} from "../../Shared/filter-bar/filter-bar.component";
import {PaginatorModule, PaginatorState} from "primeng/paginator";
import {ThreadContainerComponent} from "../../Shared/thread-container/thread-container.component";
import {ThreadsService} from "../../Services/threads.service";
import {Router, RouterLink} from "@angular/router";
import {PaginatedThreads, Threads, User} from "../../Interfaces/ReturnInterfaces";
import {TitleServiceService} from "../../Services/title-service.service";
import {NgClass, ViewportScroller} from "@angular/common";
import {UserService} from "../../Services/user.service";

@Component({
    selector: 'app-threads',
    standalone: true,
    imports: [
        NavBarComponent,
        FilterBarComponent,
        PaginatorModule,
        ThreadContainerComponent,
        NgClass,
        RouterLink
    ],
    templateUrl: './threads.component.html',
    styleUrl: './threads.component.scss'
})
export class ThreadsComponent implements OnInit {
    title:string = 'MusicBox Joinville Tópicos';
    threads?:PaginatedThreads;
    pageIndexThreads: number = 0;
    user:User | null = null;
    onlist:boolean=true;



    constructor(private titleService: TitleServiceService,
                private threadsService: ThreadsService,
                private router: Router,
                private cdr: ChangeDetectorRef,
                private viewportScroller: ViewportScroller,
                private userService: UserService,) {
    }

    ngOnInit(): void {
        this.titleService.updateTitle(this.title);
        this.fetchAuthUser();
        this.cdr.detectChanges();
        this.fetchThreads(null);
    }

    fetchThreads(page: number | null) {
        this.threadsService.getAll(page).subscribe({
            next: (resp) => {
                this.threads = resp.data;

                this.cdr.detectChanges();
            },
            error: (err) => {
                console.error('Error fetching threads:', err);
            }
        });
    }
    private fetchAuthUser() {
        this.userService.user$.subscribe({
            next: (user) => {
                this.user = user;
                this.cdr.detectChanges();
            },
            error: (err) => {
                console.log("Erro" + err)
                this.cdr.detectChanges();
            }

        });
    }


    onPageChange($event: PaginatorState) {
        if($event.page != null){
            this.pageIndexThreads = $event.page;
            this.fetchThreads($event.page+1)
            this.viewportScroller.scrollToAnchor('filterBar');
        }
    }

}
