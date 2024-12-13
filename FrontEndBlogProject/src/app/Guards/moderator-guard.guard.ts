import { CanActivateFn } from '@angular/router';

export const moderatorGuardGuard: CanActivateFn = (route, state) => {
  return true;
};
