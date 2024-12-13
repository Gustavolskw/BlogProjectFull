import {CanActivateFn, Router} from '@angular/router';
import {inject} from "@angular/core";
import {TokenService} from "../Services/token.service";

export const authGuardGuard: CanActivateFn = (route, state) => {
  const tokenServcie = inject(TokenService)
  const router = inject(Router);
  if(tokenServcie.istokenActive()){
    return true;
  }else{
    router.navigateByUrl('login');
    return false;
  }
};
