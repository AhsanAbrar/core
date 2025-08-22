<?php

describe('Segment Routing', function () {

    describe('Excluded guard', function () {
        it('does not register any package when segment is excluded');
    });

    describe('Direct match', function () {
        it('registers the matching provider and sets Package::key()');
    });

    describe('Default (root) fallback', function () {
        it('registers root when no segment is present');
        it('registers root when segment is unknown and not excluded');
    });

});
