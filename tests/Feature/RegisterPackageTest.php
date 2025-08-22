<?php

describe('Package Register', function () {

    describe('Excluded guard', function () {
        it('skips registration when the segment is excluded');
    });

    describe('Direct match', function () {
        it('registers the matching provider and sets Package::key()');
    });

    describe('Default fallback', function () {
        it('registers the root provider when no segment is present');
        it('registers the root provider when the segment is unknown');
    });

});
