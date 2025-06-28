import { TestBed } from '@angular/core/testing';

import { CutiService } from './cuti.service';

describe('CutiService', () => {
  let service: CutiService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(CutiService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
