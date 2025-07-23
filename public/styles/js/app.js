import { Application } from "./hotwired/stimulus.js";
import SearchController from "./controller/search_controller.js";


const application = Application.start();
application.register("search", SearchController);