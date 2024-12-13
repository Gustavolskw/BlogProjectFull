import { ChangeDetectorRef, Component, Input, OnInit } from '@angular/core';
import { NgClass } from '@angular/common';
import { TokenService } from "../../Services/token.service";
import { AuthService } from "../../Services/auth.service";
import { UserService } from "../../Services/user.service";
import { User } from "../../Interfaces/ReturnInterfaces";
import { Router, RouterLink, NavigationEnd } from "@angular/router";
import { filter } from 'rxjs/operators';

@Component({
  selector: 'app-nav-bar',
  standalone: true,
  imports: [NgClass, RouterLink],
  templateUrl: './nav-bar.component.html',
  styleUrls: ['./nav-bar.component.scss']
})
export class NavBarComponent implements OnInit {
  logoMainSrc = 'logoMain.png'
  @Input() headerOpt: Number | undefined = 1;
  isLoggedIn?: boolean;
  user?: User|null;


  constructor(
    private tokenService: TokenService,
    private authService: AuthService,
    private cdr: ChangeDetectorRef,
    private router: Router,
    private userService: UserService
  ) { }


  ngOnInit(): void {

    if (this.tokenService.istokenActive()) {
      this.tokenService.authStatus$.subscribe((status) => {
        this.isLoggedIn = status;

        if (status) {
          this.fetchAuthUser();
        } else {
          this.user = null;
          this.isLoggedIn = false;
        }
        this.cdr.detectChanges();
      });
    }
    this.router.events
      .pipe(filter(event => event instanceof NavigationEnd))
      .subscribe((event: any) => {
        this.updateHeaderOpt(event.url);
      });
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

  protected logoutUser(): void {
    this.authService.logout(this.tokenService.getToken()).subscribe({
      next: () => {
        this.tokenService.deleteToken(); // Emit logout event
      },
      error: (err) => console.log("Erro" + err)
    });
  }

  private updateHeaderOpt(url: string) {
    // Set headerOpt based on the current route
    if (url.includes('/home')) {
      this.headerOpt = 1;
    } else if (url.includes('/historia')) {
      this.headerOpt = 2;
    } else if (url.includes('/topicos')) {
      this.headerOpt = 3;
    } else if (url.includes('/administracao')) {
      this.headerOpt = 4;
    } else {
      this.headerOpt = 1;
    }
  }


}
