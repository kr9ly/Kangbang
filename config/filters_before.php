<?php

AdminFilter::get()->acceptByPath("admin")->rejectByPath("admin/login")->apply();