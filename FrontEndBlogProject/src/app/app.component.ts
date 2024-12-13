import {Component, Input, OnInit} from '@angular/core';
import {NavigationEnd, Router, RouterOutlet} from '@angular/router';
import {NavBarComponent} from "./Shared/nav-bar/nav-bar.component";
import {UserService} from "./Services/user.service";
import {TokenService} from "./Services/token.service";

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterOutlet, NavBarComponent],
  templateUrl: './app.component.html',
  styleUrl: './app.component.scss'
})
export class AppComponent implements OnInit{
  title = 'MusicBox Joinville';
  showNavbar = true;

  constructor(private router: Router, private userService: UserService, private tokenService: TokenService) {}

  ngOnInit(): void {
    if(this.tokenService.istokenActive()){
      this.tokenService.authStatus$.subscribe((isAuthenticated) => {
        if (isAuthenticated) {
          this.userService.getAuthenticatedUser(this.tokenService.getToken()).subscribe({
            error: (error) => {
                console.log(error.status);
                if(error.status == 401){
                this.tokenService.deleteToken();
                }
            }
          });
        } else {
          this.userService.clearUserData(); // Clear user data on logout
        }
      });
    }
    this.router.events.subscribe(event => {
      if (event instanceof NavigationEnd) {
        this.showNavbar = event.url !== '/login';
      }
    });
  }
}
