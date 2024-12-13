import { CanActivateFn, Router } from '@angular/router';
import { inject } from "@angular/core";
import { TokenService } from "../Services/token.service";
import { UserService } from "../Services/user.service";
import {catchError, first, map, Observable, of} from 'rxjs';
import { Location } from '@angular/common';

export const adminGuardGuard: CanActivateFn = (route, state): Observable<boolean> => {
  const tokenService = inject(TokenService);
  const userService = inject(UserService);
  const router = inject(Router);
  const location = inject(Location);

  if (tokenService.istokenActive()) {
    // If the token is active, fetch the authenticated user details from the server
    return userService.getAuthenticatedUser(tokenService.getToken()).pipe(
        first(),
        map((response) => {
          // Check if the user role is sufficient
          if (response.data && response.data.role >= 2) {
            return true;
          } else {
            location.back(); // Redirect back if the role is insufficient
            return false;
          }
        }),
        catchError((error) => {
          location.back(); // Redirect back on error
          return of(false);
        })
    );
  } else {
    router.navigateByUrl('login');
    return of(false);
  }
};

