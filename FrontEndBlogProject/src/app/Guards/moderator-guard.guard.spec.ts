import { TestBed } from '@angular/core/testing';
import { CanActivateFn } from '@angular/router';

import { moderatorGuardGuard } from './moderator-guard.guard';

describe('moderatorGuardGuard', () => {
  const executeGuard: CanActivateFn = (...guardParameters) => 
      TestBed.runInInjectionContext(() => moderatorGuardGuard(...guardParameters));

  beforeEach(() => {
    TestBed.configureTestingModule({});
  });

  it('should be created', () => {
    expect(executeGuard).toBeTruthy();
  });
});
